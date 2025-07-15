<?php

namespace Thomasbrillion\Notification\Support;

use Illuminate\Support\Facades\Validator as BaseValidator;

class Validator
{
    /**
     * @throws \Exception
     */
    public static function tryValidate(array $data, array $rules, array $messages = []): array
    {
        $validator = BaseValidator::make($data, $rules, $messages);

        if ($validator->fails()) {
            $errorMessage = '';
            foreach ($validator->errors()->getMessages() as $error) {
                foreach ($error as $message) {
                    $errorMessage .= $message . ' ';
                }
            }
            throw new \Exception($errorMessage, 422);
        }

        return $validator->validated();
    }
}
