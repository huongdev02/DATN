<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderDelivered extends Mailable
{
    use Queueable, SerializesModels;

    public $order; // Đảm bảo bạn có thể truy cập được thông tin đơn hàng

    /**
     * Tạo một instance của mail.
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order; // Gán đơn hàng vào thuộc tính
    }

    /**
     * Lấy envelope của thư.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Trạng thái đơn hàng của bạn đã thay đổi'
        );
    }

    /**
     * Lấy định nghĩa nội dung của thư.
     *
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.order_delivered', // Bạn có thể tạo view này trong resources/views/emails/order_delivered.blade.php
            with: [
                'order' => $this->order, // Truyền thông tin đơn hàng vào view
            ]
        );
    }

    /**
     * Lấy các file đính kèm của thư (nếu có).
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
