# Использование статического анализатора кода для обеспечения безопасности

### Обнаружен участок кода, подверженный инъекции SQL кода:

```php
$getid  = "SELECT first_name, last_name FROM users WHERE user_id = '$id';";
$result = mysqli_query($GLOBALS["___mysqli_ston"],  $getid );
```
### Результат анализа статическим анализатором кода:

![image](https://github.com/halissha/rbpzos4_static/blob/main/img.png)

### Основные проблемы:
- Пользовательские данные подставляются в запрос без плейсхолдера или экранирования спецсимволов

[MITRE, CWE-89](https://cwe.mitre.org/data/definitions/89 "MITRE, CWE-89") - Improper Neutralization of Special Elements used in an SQL Command

- Пользовательские данные не проходят проверку характеристик или вайтлист перед выполнением запроса

[MITRE, CWE-20](https://cwe.mitre.org/data/definitions/20 "MITRE, CWE-20") - Improper Input Validation

Вследствие игнорирования требований безопасности мошенник может сконструировать такой запрос в базу данных, который ему будет выгоден
