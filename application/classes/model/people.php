<?php
/**
  +-----------------------------------------------------------------
 * 名称：求是任务模型
 * 版本：1.0
 * 版权：www.usho.cn
 * 作者：zhaojiangang
 * 最后更新日期：12-10-12 上午10:48
  +-----------------------------------------------------------------
 */
class Model_People {

    //发布人物新闻
    public static function postnews($data) {

        //内容保存到数据库
        $data['create_at'] = isset($data['create_at']) ? $data['create_at'] : date('Y-m-d H:i:s');
        $news = new PeopleNews();
        $news->fromArray($data);
        $news->save();
    }

}

?>