<?php


namespace App\Validator;


use App\Validator\Constraints\ContainsAliExpressLink;
use App\Validator\Constraints\ContainsAmazonLink;
use App\Validator\Constraints\ContainsEbayCountry;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Currency;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AliExpressToEbayValidator
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
        $data = $request->request->get('aliExpressProduct');
        $stock = isset($data['stock']) ? (int) $data['stock'] : null;
        $price = isset($data['price']) ? (double) $data['price']: null;
        $title = isset($data['title']) ? $data['title'] : null;
        $description = isset($data['description']) ? $data['description'] : null;
        $category = isset($data['category']) ? $data['category'] : null;
        $shopCountry = isset($data['shopCountry']) ? $data['shopCountry'] : null;
        $image = isset($data['image']) ? $data['image'] : null;

        $input = [
            'stock' => $stock,
            'price' => $price,
            'title' => $title,
            'description' => $description,
            'category' => $category,
            'shopCountry' => $shopCountry,
            'image' => $image
        ];

            $constraints = new Collection([
                'stock' => [
                    new NotBlank(['message' => 'Please, enter product\'s stock value']),
                    new NotNull(['message' => 'Please, enter product\'s stock value']),
                    new Type(['type' =>'integer', 'message' => 'Please, enter number']),
                    new GreaterThanOrEqual(['value' => 1 ,'message' => 'Stock can\'t be negative or equal to zero'])
                    ],
                'price' => [
                    new NotBlank(['message' => 'Please, enter product\'s sales price']),
                    new NotNull(['message' => 'Please, enter product\'s sales price']),
                    new Type(['type' => 'double', 'message' => 'Price should be a number']),
                    new GreaterThanOrEqual(['value' => 0 ,'message' => 'Price can\'t be negative'])
                ],
                'title' => [
                    new NotBlank(['message' => 'Please, provide product name']),
                    new NotNull(['message' => 'Please, provide product name']),
                    new Type('string'),
                    new Length(['max' => 80, 'maxMessage' => 'Oops, product\'s name shouldn\'t exceed 80 characters!'])
                ],
                'description' => [
                    new NotBlank(['message' => 'Please, provide product\'s description']),
                    new NotNull(['message' => 'Please, provide product\'s description']),
                    new Type('string')
                ],
                'category' => [
                    new NotBlank(['message' => 'Please, select category']),
                    new NotNull(['message' => 'Please, select category'])
                ],
                'shopCountry' => [
                    new NotBlank(['message' => 'Please, select Germany or United Kingdom']),
                    new NotNull(['message' => 'Please, select Germany or United Kingdom']),
                    new ContainsEbayCountry()
                ],
                'image' => [
                    new NotBlank(['message' => 'Please, add product images']),
                    new NotNull(['message' => 'Please, add product images'])
                ]
            ]);

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