<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateItem;
use App\Http\Requests\UpdateItem;
use App\Item;
use App\Shop;
use App\Rpg;

class ItemController extends Controller
{
    public function create(CreateItem $request) 
    {
        $response = ['error' => false, 'message' => 'Item criado com sucesso!'];
        $shop = Shop::findOrFail($request->shop_id);
        $shop->load('rpg');
        $rpg = Rpg::findOrFail($shop->rpg->id);

        $this->authorize('update', $rpg);
        $shop->items()->create($request->only('name', 'gold_price', 'cash_price', 'max_units', 'require_test', 'detail'));
        return $response;
    }

    public function update(UpdateItem $request)
    {
        $response = ['error' => false, 'message' => 'Item atualizado com sucesso!'];
        $item = Item::findOrFail($request->item_id);
        $item->load('shop.rpg');
        $rpg = Rpg::findOrFail($item->shop->rpg->id);

        $this->authorize('update', $rpg);
        $item->update($request->only('name', 'gold_price', 'cash_price', 'max_units', 'require_test', 'detail'));
        if ($request->has('image')) {
            $request->file('image')->storeAs('images/items', $item->id.'.jpg');
        }
        return $response;
    }
}
