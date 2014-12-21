<?php

namespace wmf\modules\contact\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $message;
    public $captcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'message'], 'required'],
            [['name', 'email', 'message'], 'trim'],
            [['name'], 'match', 'pattern' => "/^[\p{L}\s'.-]+$/u"],
            [['name'], 'string', 'max' => 255, 'tooLong' => 'Name too long!'],
            [['email'], 'string', 'max' => 255, 'tooLong' => 'Email too long!'],
            [['message'], 'string', 'max' => 10000, 'tooLong' => 'Message too long! (10240 characters max)'],
            // email has to be a valid email address
            [['email'], 'email', 'message' => "Invalid Email Address!"],
            [['captcha'],
                'wmc\modules\recaptcha\validators\RecaptchaValidator',
                'emptyMessage' => "Please confirm you aren't a robot.",
                'incorrectMessage' => "Failed to verify reCaptcha field."
            ]
        ];
    }

    public function attributeLabels() {
        return [
            'name' => 'Your Name',
            'email' => 'Your Email',
            'message' => 'Your Message',
            'captcha' => "Please Confirm You're Human"
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();
    }
}
