<?php
namespace Modules\Controllers;

use Modules\Models\Services\Services;
use Modules\Forms\SummariesForm;

class SessionController extends ControllerBase
{

    public function indexAction()
    {
    }

    public function loginAction()
    {   
        $this->session->start();
        session_regenerate_id(true);
        $authUrl = $this->oauthProviderGithub->getAuthorizationUrl();
        $this->session->set('oauth2state', $this->oauthProviderGithub->getState());
        $this->response->redirect($authUrl, true);
        $this->response->send();
        $this->logger->info('GithubのOAuth認証ページURL ： ' . $authUrl);
        return false;
    }

    public function userAction()
    {
        $code = $this->request->get('code');
        if (empty($code)) {
            $this->flash->error('The OAuth provider information is invalid.');
            return false;

        } elseif (empty($this->request->get('state')) || ($this->request->get('state') !== $this->session->get('oauth2state'))) {
            $this->logger->log($this->request->get('state'));
            $this->logger->log($this->session->get('oauth2state'));
            $this->session->remove('oauth2state');
            $this->flash->error('Invalid state.');
            return false;

        } else {
            try{
                // 認証コードからアクセストークンを取得
                $token = $this->oauthProviderGithub->getAccessToken('authorization_code', [
                    'code' => $code
                ]);
                // トークン使って認可した情報を取得
                $userInfo = $this->oauthProviderGithub->getResourceOwner($token);
                $userInfoArray = $userInfo->toArray();
                $this->view->userInfo = $userInfoArray;
                $this->session->set('userInfo', $userInfoArray);

                //RESTAPI認証用のトークンを作成し、viewに連携
                $apiToken = $this->security->getToken();
                $this->view->apiToken = $apiToken;

                $this->logger->info('Githubから取得したcode ： ' . $code);
                $this->logger->info('Githubから取得したtoken ： ' . $token);
                $this->logger->info('Githubから取得したユーザー情報 ： ' . print_r($userInfo->toArray(), true));
                $this->dispatcher->forward(
                    [
                        'controller' => 'session',
                        'action'     => 'search'
                    ]
                );
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }
    }

    public function searchAction()
    {
        // セッションのAPIトークンの有無をチェック(直接URL叩かれた場合、エラー画面表示)
        if(!$this->security->getSessionToken()){
            $this->flash->error('ログインして下さい。');
            return false;
        }

        $form = new SummariesForm();
        $this->view->form = $form;
        $this->view->userInfo = $this->session->get('userInfo');
        $this->view->apiToken = $this->security->getSessionToken();

        if ($this->request->getPost('summaryDate')) {
            $summaries = $this->getSummariesService()->findSummaries(date($this->request->getPost('summaryDate')));
        } else {
            $summaries = $this->getSummariesService()->findAllSummaries();
        }

        $summariesArray = $summaries->toArray();
        $summaryContents = [];
        
        if(count($summariesArray) !== 0){
            foreach ($summariesArray as $index => $summary) {
                $product = $this->getProductsService()->findProductById($summary['id'], 1);

                $this->logger->info('for文の中入っとるよ');

                $summaryContents[$summary['summary_date']][$index]['delete_flg'] = $summary['delete_flg'];
                $summaryContents[$summary['summary_date']][$index]['id'] = $product->id;
                $summaryContents[$summary['summary_date']][$index]['title'] = $product->title;
                $summaryContents[$summary['summary_date']][$index]['description'] = $product->description;
                $summaryContents[$summary['summary_date']][$index]['price'] = $product->price;
                $summaryContents[$summary['summary_date']][$index]['image'] = $product->image;
                // 集計日に削除された商品数を取得
                $summaryContents[$summary['summary_date']][$index]['create_cnt'] = count($this->getSummariesService()->findSummaries($summary['summary_date'], 0));
                // 集計日に削除された商品数を取得
                $summaryContents[$summary['summary_date']][$index]['delete_cnt'] = count($this->getSummariesService()->findSummaries($summary['summary_date'], 1));
            }
        }
        $this->view->summaryContents = $summaryContents;
    }

    public function logoutAction()
    {
        // セッションをすべて破棄する
        $this->session->destroy();
        return $this->dispatcher->forward(
            [
                'controller' => 'session',
                'action'     => 'index'
            ]
        );
    }

    private function getProductsService()
    {
        return Services::getService('Products');
    }

    private function getSummariesService()
    {
        return Services::getService('Summaries');
    }
}