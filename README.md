# Использование статического анализатора кода для обеспечения безопасности

## Обнаружен участок кода, подверженный инъекции SQL кода:

```php
$getid  = "SELECT first_name, last_name FROM users WHERE user_id = '$id';";
$result = mysqli_query($GLOBALS["___mysqli_ston"],  $getid );
```
## Результат анализа статическим анализатором кода:

![image](https://github.com/halissha/rbpzos4_static/blob/main/img.png)

## Основные проблемы:
### Пользовательские данные подставляются в запрос без плейсхолдеров или экранирования

```
[MITRE, CWE-89] - Improper Neutralization of Special Elements used in an SQL Command
```

### Пользовательские данные не проходят проверку перед выполнением запроса

```
[MITRE, CWE-20] - Improper Input Validation
```

Вследствие игнорирования требований безопасности мошенник может сконструировать вредоносный запрос в базу данных.
Это может быть чревато потерей, модификацией, утечкой данных, или получением мошенником доступа к оболочке.

## Устранение угроз безопасности:

- Воспользуемся PDO для конструирования параметризованного запроса (PDO - расширение для PHP, предоставляющее разработчику универсальный интерфейс для доступа к различным базам данных.)

```php
 $statement = $pdo->prepare('SELECT first_name, last_name FROM users WHERE user_id = :id;');
 $statement->bindParam("id", $id, PDO::PARAM_INT);
 $statement->execute();
```

- Обеспечим дополнительную валидацию переменной "id" по длине

```php
 if (strlen((string)$id) == 3) {
    ...
}
```
## Запахи кода:
### Файл должен содержать пустую строку в конце

Некоторые инструменты работают лучше когда файл содержит пустую строку в конце

### Убрать закрывающий тег ?> в конце файла

Согласно стандарту PSR2 закрывающий тег не требуется использовать в файле, содержащем только php-код

### Убрать пустое место после открывающей скобки и перед закрывающей скобкой

Общий принцип следование стандартам оформления кода позволяет командной разработке быть эффективнее, а коду быть более читаемым

## Результат повышения безопасности формы:

![image](https://github.com/halissha/rbpzos4_static/blob/main/image.png)

## Использование Burp Suite для нахождения уязвимости
Настроим прослушивание прокси в Burp Suite для возможности перехвата запросов:
![image](https://github.com/halissha/rbpzos4_static/blob/main/proxy.jpg)

Аналогично настроим прокси в ручном режиме в браузере:
![image](https://github.com/halissha/rbpzos4_static/blob/main/proxy_browser.jpg)

## Перехват запросов из DVWA SQL Injection
Попробуем перехватить запрос с атакуемой страницы с помощью Interception:
![image](https://github.com/halissha/rbpzos4_static/blob/main/intercept.jpg)

## Обнаружение уязвимости с помощью Burp Suite
Проведем следующую инъекцию и получим всех пользователей, с помощью OR легко проходим валидацию:
```php
1 OR 1=1#
1+OR+1%3d1%23
```
Получим всех пользователей в ответе:

![image](https://github.com/halissha/rbpzos4_static/blob/main/users.jpg)

Теперь проведем инъекцию на обнаружение имен таблиц:
```php
1 UNION SELECT 1, TABLE_NAME FROM INFORMATION_SCHEMA.TABLES#
1+UNION+SELECT+1,TABLE_NAME+FROM+INFORMATION.SCHEMA.TABLES%23
```
Получим имена всех таблиц, в ответе можно заметить важную таблицу users:
![image](https://github.com/halissha/rbpzos4_static/blob/main/user_table.jpg)

Следующим шагом попробуем вытащить логины и пароли из таблицы users с помощью следующей инъекции:
```php
1 UNION SELECT user, password FROM users#
1+UNION+SELECT+user,+password+FROM+users%23
```
В отвече получим логины и пароли пользователей системы:
![image](https://github.com/halissha/rbpzos4_static/blob/main/logins_passwords.jpg)

## Обнаружение уязвимости с помощью SQLMap

Воспользуемся утилитой SQLMap для выявления уязвимых параметров запроса. Утилита выявила уязвимости с типами:
```php
boolean-based blind
time-based blind
```
![image](https://github.com/halissha/rbpzos4_static/blob/main/table_vulns.jpg)

Далее при помощи утилиты получим список имеющихся баз данных:
![image](https://github.com/halissha/rbpzos4_static/blob/main/dbases.jpg)

Нашли базу данных dvwa, просмотрим ее таблицы:
![image](https://github.com/halissha/rbpzos4_static/blob/main/tables.jpg)

Нашли таблицу users, просмотрим её колонки:
![image](https://github.com/halissha/rbpzos4_static/blob/main/cols.jpg)

Удалось с помощью утилиты sqlmap определить пароли пользователей по имеющимся в таблице хешам:
![image](https://github.com/halissha/rbpzos4_static/blob/main/pass.jpg)











