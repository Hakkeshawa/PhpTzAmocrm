<?php

require_once 'vendor/autoload.php';

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\WebhooksCollection;
use AmoCRM\Models\WebhookModel;

// Настройки amoCRM
$clientId = '8318cbed-f42e-41f9-a2da-4187c0d9f742';
$clientSecret = '5712Zr2Tnweix4zQ0iqFZ8kV2SDUrpwVrBbREloyzpzaYPPo9tZdTQWAk3PYD856';
$redirectUri = 'https://danilintzamocrm.netlify.app/';
$accessToken = 'def50200f5476645459a9c161d115ec4283ca42a1f4a2cd61ca1d508c90cb0f8e0c8af2e0c3a7fc14b593612e50bbfd033853ab567f4df69de5a32a7d195296f138a7e5262dee1ae68b23919dd0fbd024fb8d5a16c3a808e83aa8fb745165daee5794d87681852a4a10211a949c8f413b11781be869c921fcfe5434630dd215c9fc0cce8a694eabab09c966fabeb527210f8325996ed6cb20f16f6830c166017657f563d30c1f242d583b21b8a14d7ac2480245e9ee5be6ab1e46dab7360571ad83b0b2251a17a9e56cde42089e5ac37f388fe6db1f0374e35246cad822a5405804fea074936bdb2fe50d6f22ff2b5418bcbadb3881cbb6a3b640dbbfb813bfcb737d4a0cce9ed698c482e87bf04b1d6c374f4ccd15704c3e642b01b2a21587cfa40611acd659cc5311e5f30c6b036361bee192dfd67df0d2f121c525eb8cc61d8e0509d848c581df26a019482a4474630b683e0ad21fd8068c43a5f69527ef9c7bbaaed2cf513aee4e66f44cb5c9fca29925778c1f3633a2dd1298a05614ae1894984695e270aa17090d93b69d6c52200581c1e476020e069996f585d0110c5f161078dc96700d73fa61426594f9c425b23fd91eba49a6b6913e02fb96cd9545358d2cf7ad21dff0a3190962a83bb2eb31863a4248535442d8cbf0c113e00bb794f58ce2018dd025754c14ca13d4f105e5de9e04f3e';
$refreshToken = 'your_refresh_token';

$apiClient = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);
$apiClient->setAccessToken($accessToken);
$apiClient->setRefreshToken($refreshToken);

// Создаем коллекцию вебхуков
$webhooksCollection = new WebhooksCollection();

// Функция для добавления вебхука
function addWebhook($eventType, $url) {
    global $webhooksCollection;
    $webhook = new WebhookModel();
    $webhook->setEventType($eventType)
            ->setUrl($url);
    $webhooksCollection->add($webhook);
}

// Добавляем вебхуки для нужных событий
addWebhook('leads-add', 'https://danilintzamocrm.netlify.app/');
addWebhook('leads-update', 'https://danilintzamocrm.netlify.app/');
addWebhook('contacts-add', 'https://danilintzamocrm.netlify.app/');
addWebhook('contacts-update', 'https://danilintzamocrm.netlify.app/');

// Отправляем вебхуки на сервер amoCRM
$apiClient->webhooks()->subscribe($webhooksCollection);

// Функция для добавления текстового примечания
function addNote($entityId, $entityType, $noteText) {
    global $apiClient;
    $note = $apiClient->notes()->createSimple($entityId, $entityType, $noteText);
    return $note;
}

// Обработчик вебхуков
$webhookHandler = function ($data) {
    global $apiClient;

    $entityType = $data['entity_type'];
    $entityId = $data['entity_id'];
    $event = $data['event'];

    // Получаем информацию о сущности (сделке или контакте)
    $entity = $apiClient->entities()->get($entityType, $entityId);

    // Формируем текстовое примечание в зависимости от события
    $noteText = '';
    switch ($event) {
        case 'leads-add':
        case 'contacts-add':
            $responsibleUser = $entity->getResponsibleUser();
            $createdAt = $entity->getCreatedAt()->format('Y-m-d H:i:s');
            $noteText = "Создано: {$entity->getName()}, Ответственный: {$responsibleUser->getName()}, Время: {$createdAt}";
            break;

    }

    // Добавляем текстовое примечание
    if (!empty($noteText)) {
        addNote($entityId, $entityType, $noteText);
    }
};

// Получаем данные из входящего запроса
$requestBody = file_get_contents('php://input');
$data = json_decode($requestBody, true);

// Обработка вебхука
if (!empty($data)) {
    $webhookHandler($data);
}
