<?php

namespace backend\controllers;

use Yii;
use backend\models\OaGoods;
use backend\models\OaGoodsSearch;
use backend\models\OaGoodsinfo;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
/**
 * OaCheckController implements the CRUD actions for OaGoods model.
 */
class OaCheckController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all OaGoods models.
     * @return mixed
     */


    public function actionToCheck()
    {
        $searchModel = new OaGoodsSearch();
        //待审批产品
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,'','待审批');

        return $this->render('toCheck', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Action of Pass
     * @param integer $id
     * @return mixed
     */
    public function actionPassForm($id)
    {
        $model = $this->findModel($id);
        return $this->renderAjax('passForm',[
            'model' => $model
        ]);

    }
    /**
     * Action of Pass
     * @return mixed
     */
    public function actionPass()
    {
        $request = yii::$app->request->post()['OaGoods'];
        $id = $request['nid'];
        $approvalNote = $request['approvalNote'];
        $model = $this->findModel($id);
        $_model = new OaGoodsinfo();
        $user = yii::$app->user->identity->username;
        $model ->checkStatus = '已审批';
        $model ->approvalNote = $approvalNote;
        $model ->developer = $user;
        $model ->updateDate = strftime('%F %T');
        $model->update(false);
        //审批状态改变之后就插入数据到OaGoodsInfo
        $nid = $model->nid;
        $img = $model->img;
        $developer = $model->developer;
        $_model->isNewRecord = true;
        $_model->goodsid =$nid;
        $_model->picUrl = $img;
        $_model->developer =$developer;
        $_model->devDatetime =strftime('%F %T');;
        $_model->updateTime =strftime('%F %T');;
        $_model->achieveStatus='待处理';
        $_model->GoodsName='';
        return $this->redirect(['to-check']);

    }


    /**
     * Action of Pass Lots
     * @return mixed
     */
    public function actionPassLots()
    {
        $_model = new OaGoodsinfo();
        $ids = yii::$app->request->post()["id"];
        foreach ($ids as $id)
        {

            $model = $this->findModel($id);
            $model->checkStatus ='已审批';
            $model->update(['checkStatus']);
            //插入到OagoodsInfo里面
            $developer = $model->developer;
            $_model = clone $_model;
            $nid = $model->nid;
            $img = $model->img;
            $_model->isNewRecord = true;
            $_model->goodsid =$nid;
            $_model->picUrl = $img;
            $_model->developer =$developer;
            $_model->devDatetime =strftime('%F %T');;
            $_model->updateDatetime =strftime('%F %T');;
            $_model->achieveStatus='待处理';
            $_model->GoodsName='';
            $_model->save(false);
        }
        return $this->redirect(['to-check']);
    }

    /**
     * Action of Pass
     * @param integer $id
     * @return mixed
     */
    public function actionFailForm($id)
    {
        $model = $this->findModel($id);
        return $this->renderAjax('failForm',[
            'model' => $model
        ]);

    }
    /**
     * Action of Fail
     * @return mixed
     */
    public function actionFail()
    {
        $request = yii::$app->request->post()['OaGoods'];
        $id = $request['nid'];
        $approvalNote = $request['approvalNote'];
        $model = $this->findModel($id);
        $user = yii::$app->user->identity->username;
        $model ->checkStatus = '未通过';
        $model ->approvalNote = $approvalNote;
        $model ->developer = $user;
        $model ->updateDate = strftime('%F %T');
        $model->update(false);
        return $this->redirect(['to-check']);
    }


    /**
     * Action of Fail Lots
     * @return mixed
     */
    public function actionFailLots()
    {
        $ids = yii::$app->request->post()["id"];
        foreach ($ids as $id)
        {
            $model = $this->findModel($id);
            $model->checkStatus ='未通过';
            $model->update(['checkStatus']);
        }
        return $this->redirect(['to-check']);
    }

    /**
     * Action of Fail
     * @param integer $id
     * @return mixed
     */
    public function actionTrash($id)
    {
        $model = $this->findModel($id);
        $user = yii::$app->user->identity->username;
        $model ->checkStatus = '已作废';
        $model ->developer = $user;
        $model ->updateDate = strftime('%F %T');
        $model->update(array('checkStatus','developer','updateDate'));
        return $this->redirect(['to-check']);
    }


    /**
     * Action of Fail Lots
     * @return mixed
     */
    public function actionTrashLots()
    {
        $ids = yii::$app->request->post()["id"];
        foreach ($ids as $id)
        {
            $model = $this->findModel($id);
            $model->checkStatus ='已作废';
            $model->update(['checkStatus']);
        }
        return $this->redirect(['to-check']);
    }


    /**
     * Action of Passed
     * @return mixed
     */
    public function actionPassed()
    {
        $searchModel = new OaGoodsSearch();
        // 已审批产品
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,'','已审批');
        return $this->render('passed', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }



    /**
     * Action of Failed
     * @return mixed
     */
    public function actionFailed()
    {
        $searchModel = new OaGoodsSearch();
        //未通过产品
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,'','未通过');
        return $this->render('failed', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single OaGoods model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new OaGoods model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OaGoods();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->nid]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OaGoods model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->nid]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing OaGoods model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    public function actionHeart($id)
    {
        $model = $this->findModel($id);

        return $this->renderAjax('heart',[
            'model' => $model,
        ]);
    }


    // Forward action

    public function actionForward($id)
    {
        $model = $this->findModel($id);
        $user = yii::$app->user->identity->username;
        $model ->devStatus = '正向认领';
        $model ->developer = $user;
        $model ->updateDate = strftime('%F %T');
        $model->update(array('devStatus','developer','updateDate'));
        return $this->redirect(['index']);
    }

    //Backward action

    public function actionBackward($id)
    {
        $model = $this->findModel($id);
        $user = yii::$app->user->identity->username;
        $model ->devStatus = '逆向认领';
        $model ->developer = $user;
        $model ->updateDate = strftime('%F %T');
        $model->update(array('devStatus','developer','updateDate'));
        return $this->redirect(['index']);
    }


    // forward products
    public function actionForwardProducts()
    {
        $searchModel = new OaGoodsSearch();
        $dataProvider = new ActiveDataProvider([
            'query' => OaGoods::find()->where(['devStatus'=>'正向认领']),
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);
        return $this->render('forwardProducts', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // backward products
    public function actionBackwardProducts()
    {
        $searchModel = new OaGoodsSearch();
        $dataProvider = new ActiveDataProvider([
            'query' => OaGoods::find()->where(['devStatus'=>'逆向认领']),
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);
        return $this->render('backwardProducts', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Finds the OaGoods model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OaGoods the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OaGoods::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
