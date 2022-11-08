# Использование статического анализатора кода для обеспечения безопасности

## Обнаружен участок кода, подверженный инъекции SQL кода:

```php
$getid  = "SELECT first_name, last_name FROM users WHERE user_id = '$id';";
$result = mysqli_query($GLOBALS["___mysqli_ston"],  $getid );
```
## Результат анализа статическим анализатором кода:

![image](https://github.com/halissha/rbpzos4_static/blob/main/img.png)

## Основные проблемы:
### Пользовательские данные подставляются в запрос без плейсхолдера или экранирования спецсимволов

```
[MITRE, CWE-89] - Improper Neutralization of Special Elements used in an SQL Command
```

### Пользовательские данные не проходят проверку или вайтлист перед выполнением запроса

```
[MITRE, CWE-20] - Improper Input Validation
```

Вследствие игнорирования требований безопасности мошенник может сконструировать вредоносный запрос в базу данных.
Это может быть чревато потерей, модификацией, утечкой данных, или получением мошенником доступа к оболочке

## Устранение угроз безопасности:




