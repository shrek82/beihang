<div id="comment_list">

    <?php if(count($comments) == 0): ?>
    暂无任何讨论。抢个沙发？
    <?php else: ?>

    <?= $pager ?>
    <?php foreach($comments as $cmt): ?>
    <table id="comment_<?= $cmt['id'] ?>" class="comment_box" width="100%">
        <tr>
            <td class="center" width="50">
                <?= View::factory('inc/user/avatar', array('id'=>$cmt['user_id'], 'size'=>48)) ?>
                <a href="<?= URL::site('user_home?id='.$cmt['user_id']) ?>" class="commentor"><?= $cmt['User']['realname'] ?></a>
            </td>
            <td>
                <div class="comment_content candyCorner">
                    <?= $cmt['content'] ?>
                </div>
                <div class="quiet" style="text-align: right">
                    <?= $cmt['post_at'] ?> |

                    <?php if($cmt['user_id'] == $_SESS->get('id')): // 能修改自己 ?>
                    <a href="javascript:modify_comment(<?= $cmt['id'] ?>)">修改</a> |
                    <?php endif; ?>

                    <a href="javascript:quote_comment(<?= $cmt['id'] ?>)">引用</a>
                </div>
            </td>
        </tr>
    </table>
    <?php endforeach; ?>
    <?= $pager ?>

    <?php endif; ?>

</div>