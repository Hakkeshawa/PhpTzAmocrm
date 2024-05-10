import requests

# Замените значения переменных на свои
client_id = "8318cbed-f42e-41f9-a2da-4187c0d9f742"
client_secret = "5712Zr2Tnweix4zQ0iqFZ8kV2SDUrpwVrBbREloyzpzaYPPo9tZdTQWAk3PYD856"
authorization_code = "def502002945ae4277cf135190919890b8ae3284e29bfba8e9471664b396a05cb7d4984edb3f07175e45f8c848d73f0ea5584ad634da73c128912629f17ae5f2e97fcc317114ff3b5a2aec223c6fc0413c2da6e7e8411f67fec8889becee005701a7f062e6f52e445c096833c3326d55b8812dab494387c2ae729d505e6c188269506fe0aee5a8c52fd0c38bc6890aeea9193897a15c1f7c1558cf90574349109aa4f5e0a0e1e3957eaf4ac641336fabe81ea42abd9e498e3460dfd7d0208864f794a758dd4dcb34729573095d4b31690e2bb86f7c7733957aa4f6fe431681456098ae8f56743bc89dc7ec0dc14d07f27f5e57adfaffe4ddda8ae75f98281a9cd1b6d868a6bf434e94698577f0a4f6d029bc58dd13f75e7206397b380390c6ee1bc9acbd169bf37452e9893daaafd56ba2d734c1fdd59d1b71b5d2cb1b7d43d4eae7b875fe60b53a8e4cdab88bfcbe5a5485bd7be67e77f16955fc3b03c8b418164f6ab052813ad977609ec1a1d53f586533e66975a4ba22596d1f9f0d1028d7d1360173296c07028113b5989687173bc46c9738870696482722e350cea42262ea25f1f134663df648dd933df0ccb8442916ece3df740b9ea3d55bb61a1fa13da4df23eea42a8419d5f78420dc2cbe219ddbce73c18f1086b9feac3bc7459f4f7dcdfe8dbc31610f5facf3e4039f9315d60302974eac"
redirect_uri = "https://danilintzamocrm.netlify.app/"

# Формируем данные для запроса
data = {
    "client_id": client_id,
    "client_secret": client_secret,
    "grant_type": "authorization_code",
    "code": authorization_code,
    "redirect_uri": redirect_uri
}

# Выполняем POST запрос
response = requests.post("https://subdomain.amocrm.ru/oauth2/access_token", json=data)

# Проверяем статус ответа
if response.status_code == 200:
    # Получаем данные из ответа
    response_data = response.json()
    access_token = response_data["access_token"]
    refresh_token = response_data["refresh_token"]
    # Ваш access_token доступен в переменной access_token
    print("Access token:", access_token)
    print("Refresh token:", refresh_token)
else:
    print("Ошибка при получении access token:", response.text)
