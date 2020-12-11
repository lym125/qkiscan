<?php

namespace App\Console\Commands;

use App\Jobs\NotifyTransactionSynced;
use App\Models\Settings;
use App\Models\Transactions;
use Illuminate\Console\Command;

class NotifyTransactionSyncedDispatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatch:notify-transaction-synced';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '分发通知交易同步队列';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lastTxId = $this->getLastTxId();

        Transactions::chunkById(200, function ($transactions) {
            foreach ($transactions as $transaction) {
                NotifyTransactionSynced::dispatch($transaction);
            }

            $this->setLastTxId($transactions->last()->id);
        });
    }

    protected function getLastTxId()
    {
        $txId = Settings::where('key', 'notify_last_tx_id')->value('value');

        if (null === $txId) {
            $txId = Transactions::latest('id')->value('id');
        }

        return $txId;
    }

    protected function setLastTxId($txId)
    {
        Settings::updateOrCreate(['key' => 'notify_last_tx_id'], ['value' => $txId]);
    }
}
