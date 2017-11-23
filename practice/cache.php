<?php

function($date, $type, $cache) {
  $userId = Yii::$app->user->id;

  if (isset($userId) && !empty($userId)) {
    // Пробуем извлечь $data из кэша по составному ключу
    $dataJson = $cache->get($date.'-'.$type.'-'.$userId);

    if (!empty($dataJson)) {
      $dataList = json_decode($dataJson, true);
    } else {
      $dataList = SomeDataModel::find()->where(['date' => $date, 'type' => $type, 'user_id' => $userId])->all();

      if (!empty($dataList)) {
        $dataJson = json_encode($dataList, JSON_UNESCAPED_UNICODE);
      } else {
        return false;
      }

      // Сохраняем значение $dataJson в кэше. Данные можно получить в следующий раз.
      $cache->set($dataJson, $date.'-'.$type.'-'.$userId);
    }
  }

  if (!empty($dataList)) {
    $result = [];
    foreach ($dataList as $dataItem) {
      $result[$dataItem->id] = ['a' => $dataItem->a, 'b' => $dataItem->b];
    }
  }

  return $result;
}
