<?php

namespace StripeIntegration\Payments\Cron;

class WebhooksPing
{
    public function __construct(
        \StripeIntegration\Payments\Model\ResourceModel\Webhook\Collection $webhooksCollection,
        \StripeIntegration\Payments\Helper\WebhooksSetup $webhooksSetup
    ) {
        $this->webhooksCollection = $webhooksCollection;
        $this->webhooksSetup = $webhooksSetup;
    }

    public function execute()
    {
        \StripeIntegration\Payments\Helper\Logger::log("Running");
        $configurations = $this->webhooksSetup->getStoreViewAPIKeys();
        $processed = [];

        foreach ($configurations as $configuration)
        {
            $secretKey = $configuration['api_keys']['sk'];
            if (empty($secretKey))
                continue;

            if (in_array($secretKey, $processed))
                continue;

            $processed[$secretKey] = $secretKey;

            \StripeIntegration\Payments\Helper\Logger::log("Pinging");
            \Stripe\Stripe::setApiKey($secretKey);
            \Stripe\Product::create([
               'name' => 'Webhook Ping',
               'type' => 'service',
               'metadata' => [
                    "pk" => $configuration['api_keys']['pk']
               ]
            ]);
        }
    }
}
