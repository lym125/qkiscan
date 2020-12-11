<?php

namespace App\Jobs;

use App\Models\Address;
use App\Models\Transactions;
use App\Notifications\TransactionSynced;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Qkiscan\Xmpush\Xmpush\Builder;
use Qkiscan\Xmpush\Xmpush\IOSBuilder;

class NotifyTransactionSynced implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 如果任务的模型不再存在，则删除该任务.
     *
     * @var bool
     */
    public $deleteWhenMissingModels = true;

    /**
     * @var \App\Models\Transactions
     */
    protected $transaction;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Transactions $transaction
     * @return void
     */
    public function __construct(Transactions $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->notifyFrom($this->transaction);

        $this->notifyTo($this->transaction);
    }

    /**
     * 通知交易发送方
     *
     * @param \App\Models\Transactions $transaction
     * @return void
     */
    protected function notifyFrom(Transactions $transaction)
    {
        if (empty($transaction->from)) {
            return;
        }

        $from = Address::where('address', $transaction->from)->first();

        if (null === $from) {
            return;
        }

        $msg = new Builder();
        $msg->title('交易提醒');
        $msg->description('您有一条新的交易信息');
        $msg->passThrough(0);
        $msg->notifyId($transaction->id);
        $msg->extra(Builder::notifyEffect, 1);          // 此处设置预定义点击行为，1为打开app
        $msg->extra(Builder::notifyForeground, 1);      // 应用在前台是否展示通知，如果不希望应用在前台时候弹出通知，则设置这个参数为0

        $iosmsg = new IOSBuilder();
        $iosmsg->title('交易提醒');
        $iosmsg->body('您有一条新的交易信息');
        $iosmsg->soundUrl('default');
        $iosmsg->badge('4');

        foreach ($from->devices as $device) {
            $device->notify(
                new TransactionSynced(
                    $device->isIOS() ? $iosmsg : $msg
                )
            );
        }
    }

    /**
     * 通知交易接收方
     *
     * @param \App\Models\Transactions $transaction
     * @return void
     */
    protected function notifyTo(Transactions $transaction)
    {
        if (empty($transaction->payee) && empty($transaction->to)) {
            return;
        }

        $to = Address::where('address', $transaction->payee ?: $transaction->to)->first();

        if (null === $to) {
            return;
        }

        $msg = new Builder();
        $msg->title('交易提醒');
        $msg->description('您有一条新的交易信息');
        $msg->passThrough(0);
        $msg->notifyId($transaction->id);
        $msg->extra(Builder::notifyEffect, 1);          // 此处设置预定义点击行为，1为打开app
        $msg->extra(Builder::notifyForeground, 1);      // 应用在前台是否展示通知，如果不希望应用在前台时候弹出通知，则设置这个参数为0

        $iosmsg = new IOSBuilder();
        $iosmsg->title('交易提醒');
        $iosmsg->body('您有一条新的交易信息');
        $iosmsg->soundUrl('default');
        $iosmsg->badge('4');

        foreach ($to->devices as $device) {
            $device->notify(
                new TransactionSynced(
                    $device->isIOS() ? $iosmsg : $msg
                )
            );
        }
    }
}
