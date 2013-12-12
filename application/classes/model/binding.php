<?php
/**
  +-----------------------------------------------------------------
 * 名称：微博绑定模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:27
  +-----------------------------------------------------------------
 */

class Model_Binding {

    //发送站内信
    public static function create($data) {
        if (isset($data['service']) AND isset($data['uid'])) {
            $binging = new WeiboBinding();
            $data['create_at'] = date('Y-m-d H:i:s');
            $data['update_at'] = date('Y-m-d H:i:s');
            $binging->fromArray($data);
            $binging->save();
            return $binging->id;
        } else {
            return false;
        }
    }

    //修改绑定资料
    public static function update($id, $data) {
        if ($id AND $data) {
            $binding = Doctrine_Query::create()
                    ->from('WeiboBinding')
                    ->where('id = ?', $id)
                    ->fetchOne();
            $data['update_at'] = date('Y-m-d H:i:s');
            $binding->fromArray($data);
            $binding->save();
            //self::updateUserInfo($data);
            return $binding->id;
        } else {
            return false;
        }
    }

    //根据绑定序号查询
    public static function getBindingById($id) {
        if (!$id) {
            return false;
        }
        $binding = Doctrine_Query::create()
                ->from('WeiboBinding')
                ->where('id=?', $id)
                ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        return $binding;
    }

    //根据服务商和用户查询
    public static function getBinding($search) {
        if (!isset($search['service'])) {
            return false;
        }

        if (!$search['service']) {
            return false;
        }

        $binding = Doctrine_Query::create()
                ->from('WeiboBinding');

        //查询字段
        if (isset($search['fields'])) {
            $binding->select($search['fields']);
        }

        //指定服务商
        $binding->where('service=?', $search['service']);

        //根据校友id，优先查询校友会id
        if (isset($search['aa_id']) AND $search['aa_id'] >= 0) {
            $binding->addWhere('aa_id = ?', $search['aa_id']);
        }
        //根据用户id
        elseif (isset($search['user_id']) AND $search['user_id'] > 0) {
            $binding->addWhere('user_id = ?', $search['user_id']);
        } else {
            return false;
        }

        $binding = $binding->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
        return $binding;
    }

    //补充个人基本相关信息
    public static function updateUserInfo($data) {
        if (isset($data['user_id']) AND $data['user_id'] > 0) {
            $user = Doctrine_Query::create()
                    ->select('id,intro,homepage')
                    ->from('User')
                    ->where('id = ?', $data['user_id'])
                    ->fetchOne();
            if ($user) {
                if (empty($user['intro']) AND isset($data['description'] )) {
                    $user['intro'] = $data['description'];
                }
                if (empty($user['homepage']) AND isset($data['uid'])) {
                    $user['homepage'] = 'http://weibo.com/u/' . $data['uid'];
                }
                $user->update_date = date('Y-m-d H:i:s');
                $user->save();
            }
        }
    }

    //方法结束
}

?>
