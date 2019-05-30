<?php


namespace App\Validator;


use App\Validator\Constraints\ContainsAliExpressLink;
use App\Validator\Constraints\ContainsAmazonLink;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LinkInputValidator
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * LinkInputValidator constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function validate(Request $request)
    {
        $data = $request->request->get('item_import');
        $importLink = $data['importLink'];
        $importSource = $data['importSource'];

        $input = ['importLink' => $importLink];

        $constraints = new Collection([]);

        if ((int)$importSource === 1) {
            $constraints = new Collection([
                'importLink' => [
                    new NotBlank(),
                    new NotNull(),
                    new ContainsAliExpressLink()],
            ]);
        } elseif ((int)$importSource === 2) {
            $constraints = new Collection([
                'importLink' => [
                    new NotBlank(),
                    new NotNull(),
                    new ContainsAmazonLink()],
            ]);
        }

        $violations = $this->validator->validate($input, $constraints);

        $errorMessages = [];

        if (count($violations) > 0) {
            $accessor = PropertyAccess::createPropertyAccessor();
            foreach ($violations as $violation) {
                $accessor->setValue($errorMessages, $violation->getPropertyPath(), $violation->getMessage());
            }
        }
        return $errorMessages;
    }
}