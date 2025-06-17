<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'title' => $this->title,
            'price' => $this->formatPrice($this->price, $request),
        ];
    }

    // сырая краткая логика согласно заданию, которую, по-хорошему, нужно выносить. В реальной ситуации должна быть модель и сервис.
    protected function formatPrice(float $price, Request $request): string
    {
        $values['RUB'] = 1;
        $values['USD'] = 90;
        $values['EUR'] = 100;
        $defaultCurrency = 'RUB';
        $queryCurrency = $request->query('currency', $defaultCurrency);
        $currency = key_exists($queryCurrency, $values) ? $queryCurrency : $defaultCurrency;
        $newValue = match ($currency) {
            'RUB' => number_format($price * $values[$currency], 0, '.', ' ') . ' ₽',
            'USD' => '$' . number_format($price * $values[$currency], 2, '.', ' '),
            'EUR' => '€' . number_format($price * $values[$currency], 2, '.', ' '),
        };
        return $newValue;
    }
}
