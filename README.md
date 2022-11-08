# Использование статического анализатора кода для обеспечения безопасности

## Обнаружим участок кода, подверженный инъекции SQL кода:

```php
$getid  = "SELECT first_name, last_name FROM users WHERE user_id = '$id';";
$result = mysqli_query($GLOBALS["___mysqli_ston"],  $getid );
```
### Результат анализа статическим анализатором кода:

![image](https://github.com/halissha/rbpzos4_static/blob/main/img.png)
