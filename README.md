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

- Обеспечим дополнительную валидацию переменной "id" по типу и длине

```php
 if (is_numeric($id) && strlen((string)$id) == 3) {
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
Настроим прокси в Burp Suite для возможности перехвата запросов:



