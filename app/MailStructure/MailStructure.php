<?php

namespace App\MailStructure;

use Exception;
use App\Mail\Sendmail;
use App\Models\AppSetting;
use App\Models\EmailConfig;
use App\Mail\NewAccountMail;
use App\Mail\OrderPlaceMail;
use App\Mail\JobInterviewMail;
use App\Mail\StatusChangeMail;
use App\Mail\JobApplicationMail;
use App\Mail\ReturnCartOrderMail;
use App\Mail\JobApplicationStatus;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequestForgetPasswordMail;
use App\Mail\ReturnCartOrderStatusChangeMail;

class MailStructure
{
    /**
     * @throws Exception
     */
    public function EmailConfig(): void
    {
        $emailConfig = EmailConfig::first();
        
        if (!$emailConfig->emailConfigName) {
            throw new Exception("Email config name is not set");
        }

        config([
            'mail.mailers.smtp.host' => $emailConfig->emailHost,
            'mail.mailers.smtp.port' => $emailConfig->emailPort,
            'mail.mailers.smtp.encryption' => $emailConfig->emailEncryption,
            'mail.mailers.smtp.username' => $emailConfig->emailUser,
            'mail.mailers.smtp.password' => $emailConfig->emailPass,
            'mail.mailers.smtp.local_domain' => env('MAIL_EHLO_DOMAIN'),
            'mail.from.address' => $emailConfig->emailUser,
            'mail.from.name' => $emailConfig->emailConfigName,
        ]);
    }

    /**
     * @throws Exception
     */
    public function orderPlace($userMail, $OrderInfo): void
    {
        $this->EmailConfig();
        $product = array_column($OrderInfo['cart_order_product'], 'product');
        $productQuantities = array_column($OrderInfo['cart_order_product'], 'productQuantity');
        $appSetting = AppSetting::where('id', 1)->first();

        $data = [
            'invoiceId' => $OrderInfo['id'],
            'date' => $OrderInfo['date'],
            'totalAmount' => $OrderInfo['totalAmount'],
            'paidAmount' => $OrderInfo['paidAmount'],
            'deliveryFee' => $OrderInfo['deliveryFee'],
            'dueAmount' => $OrderInfo['due'],
            'couponAmount' => $OrderInfo['couponAmount'],
            'deliveryAddress' => $OrderInfo['deliveryAddress'],
            'OrderStatus' => $OrderInfo['orderStatus'],

            //customer info
            'customerName' => $OrderInfo['customer']['username'],
            'customerEmail' => $OrderInfo['customer']['email'],
            'customerPhone' => $OrderInfo['customer']['phone'],

            'product' => $product,
            'productQuantities' => $productQuantities,

            'companyName' => $appSetting['companyName'],
            'tagLine' => $appSetting['tagLine'],
            'address' => $appSetting['address'],
            'phone' => $appSetting['phone'],
            'email' => $appSetting['email'],
            'website' => $appSetting['website'],
            
        ];

        $email = Mail::to($userMail)
            ->send(new OrderPlaceMail('emails.OrderPlace',
                "Your order has been placed!", $data));

        if (!$email) {
            throw new Exception("Email not sent");
        }
    }

    /**
     * @throws Exception
     */
    public function statusChange($userMail, $OrderInfo): void
    {
        $this->EmailConfig();
        $appSetting = AppSetting::where('id', 1)->first();

        $data = [
            'invoiceId' => $OrderInfo['id'],
            'date' => $OrderInfo['date'],
            'deliveryAddress' => $OrderInfo['deliveryAddress'],
            'OrderStatus' => $OrderInfo['orderStatus'],
            'deliveryFee' => $OrderInfo['deliveryFee'],

            'customerName' => $OrderInfo['customer']['username'],
            'customerEmail' => $OrderInfo['customer']['email'],
            'customerPhone' => $OrderInfo['customer']['phone'],

             'companyName' => $appSetting['companyName'],
             'tagLine' => $appSetting['tagLine'],
             'address' => $appSetting['address'],
             'phone' => $appSetting['phone'],
             'email' => $appSetting['email'],
             'website' => $appSetting['website'],
        ];

        $email = Mail::to($userMail)
            ->send(new StatusChangeMail('emails.StatusChange',
                "Your order status has been changed!", $data));

        if (!$email) {
            throw new Exception("Email not sent");
        }
    }

    /**
     * @throws Exception
     */

    public function returnOrder($customer, $returnOrder, $returnPoduct, $returnOrderData ): void
    {
        $this->EmailConfig();
        $appSetting = AppSetting::where('id', 1)->first();

        $product = array_column($returnPoduct, 'product');
        $productQuantities = array_column($returnPoduct, 'returnProductQuantity');
        
        $data = [

            'invoiceId' => $returnOrder['cartOrderId'],
            'date' => $returnOrder['date'],

            'totalAmount' => $returnOrder['totalAmount'],
            'note'=> $returnOrder['note'],
            'returnType' => $returnOrder['returnType'],
            'returnCartOrderStatus' => $returnOrder['returnCartOrderStatus'],

            'product' => $product,
            'productQuantities' => $productQuantities,

            'customerName' => $customer['username'],
            'customerEmail' => $customer['email'],
            'customerPhone' => $customer['phone'],

             'companyName' => $appSetting['companyName'],
             'tagLine' => $appSetting['tagLine'],
             'address' => $appSetting['address'],
             'phone' => $appSetting['phone'],
             'email' => $appSetting['email'],
             'website' => $appSetting['website'],
        ];
        
        $email = Mail::to( $customer['email'])
            ->send(new ReturnCartOrderMail('emails.ReturnOrder',
                "Your Return Cart Order Has Been Placed!", $data));

        if (!$email) {
            throw new Exception("Email not sent");
        }
    }

    /**
     * @throws Exception
     */
    public function returnCartOrderStatusChange($customer, $OrderInfo): void
    {
        $this->EmailConfig();
        $appSetting = AppSetting::where('id', 1)->first();

        $data = [
            'invoiceId' => $OrderInfo['cartOrderId'],
            'date' => $OrderInfo['date'],

            'totalAmount' => $OrderInfo['totalAmount'],
            'note'=> $OrderInfo['note'],
            'returnType' => $OrderInfo['returnType'],
            'returnCartOrderStatus' => $OrderInfo['returnCartOrderStatus'],

            'customerName' => $customer['username'],
            'customerEmail' => $customer['email'],
            'customerPhone' => $customer['phone'],

             'companyName' => $appSetting['companyName'],
             'tagLine' => $appSetting['tagLine'],
             'address' => $appSetting['address'],
             'phone' => $appSetting['phone'],
             'email' => $appSetting['email'],
             'website' => $appSetting['website'],
        ];
        
        $email = Mail::to($customer)
            ->send(new ReturnCartOrderStatusChangeMail('emails.ReturnOrderStatusChange',
                "Your Return Cart order status has been changed!", $data));

        if (!$email) {
            throw new Exception("Email not sent");
        }
    }

    /**
     * @throws Exception
     */
    public function newAccount($userMail, $user): void
    {
        $this->EmailConfig();

        
        $email = Mail::to($userMail)
            ->send(new NewAccountMail('emails.NewAccount',
                "Your account has been created!", $user));

        if (!$email) {
            throw new Exception("Email not sent");
        }
    }

    /**
     * @throws Exception
     */
    public function requestForgetPassword($userMail, $mailData): void
    {
        $this->EmailConfig();

        $email = Mail::to($userMail)
            ->send(new RequestForgetPasswordMail($mailData));

        if (!$email) {
            throw new Exception("Email not sent");
        }
    }

    /**
     * @throws Exception
     */

     public function jobApplicationMail($userMail, $mailData): void
    {
        $this->EmailConfig();

        $email = Mail::to($userMail)
            ->send(new JobApplicationMail($mailData));

        if (!$email) {
            throw new Exception("Email not sent");
        }

    }

    /**
     * @throws Exception
     */
    public function jobApplicationStatusMail($userMail, $mailData): void
    {
        $this->EmailConfig();

        $email = Mail::to($userMail)
            ->send(new JobApplicationStatus($mailData));

        if (!$email) {
            throw new Exception("Email not sent");
        }
    }

    /**
     * @throws Exception
     */

    public function jobInterviewMail($userMail, $mailData): void
    {
        $this->EmailConfig();

        $email = Mail::to($userMail)
            ->send(new JobInterviewMail($mailData));

        if (!$email) {
            throw new Exception("Email not sent");
        }
    }

  
}