<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Repos\Order as OrderRepo;
use App\Repos\User as UserRepo;

class TradeList extends Builder
{

    public function handleOrders(array $trades)
    {
        $orders = $this->getOrders($trades);

        foreach ($trades as $key => $trade) {
            $trades[$key]['order'] = $orders[$trade['order_id']] ?? new \stdClass();
        }

        return $trades;
    }

    public function handleUsers($trades)
    {
        $users = $this->getUsers($trades);

        foreach ($trades as $key => $trade) {
            $trades[$key]['owner'] = $users[$trade['owner_id']] ?? new \stdClass();
        }

        return $trades;
    }

    public function getOrders($trades)
    {
        $ids = kg_array_column($trades, 'order_id');

        $orderRepo = new OrderRepo();

        $orders = $orderRepo->findByIds($ids, ['id', 'sn', 'subject', 'amount']);

        $result = [];

        foreach ($orders->toArray() as $order) {
            $result[$order['id']] = $order;
        }

        return $result;
    }

    public function getUsers($trades)
    {
        $ids = kg_array_column($trades, 'owner_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findShallowUserByIds($ids);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
