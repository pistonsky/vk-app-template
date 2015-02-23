<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\filters;

/**
 * VkAuth is an action filter that supports the authentication based on the uid and auth_key passed from vk.com
 *
 * @author Aleksandr Tsygankov <tsygankov.aleksandr@gmail.com>
 * @since 2.0
 */
class VkAuth extends \yii\filters\auth\AuthMethod
{
    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        if ($request->isPost)
        {
            $uid = $request->post('uid');
            $auth_key = $request->post('auth_key');
        } else {
            $uid = $request->get('viewer_id');
            $auth_key = $request->get('auth_key');
        }

        if (!empty($uid) && !empty($auth_key)) {
            $identity = $user->loginByAuthKey($uid, $auth_key, get_class($this));
            if ($identity !== null) {
                return $identity;
            }
        }
        if (($uid !== null) && ($auth_key !== null)) {
            header('Content-type: application/json');
            echo json_encode([
                'error' => [
                    'code' => WrongAuthKey,
                    'msg' => "auth_key is wrong for uid " . $uid
                ]
            ]);
            exit;
        }

        return null;
    }
}
