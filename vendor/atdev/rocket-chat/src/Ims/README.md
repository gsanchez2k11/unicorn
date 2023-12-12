You have to be [logged in](https://github.com/alekseykuleshov/rocket-chat#login) and have relevant permissions.

### DIRECT MESSAGE LISTING

```php
$listing = \ATDev\RocketChat\Ims\Im::listing();

if (!$listing) {

	// Log the error
	$error = \ATDev\RocketChat\Ims\Im::getError();
}
```

### CREATE DIRECT MESSAGE SESSION

```php
$im = new \ATDev\RocketChat\Ims\Im();

$im->setUsername("[USERNAME]");
// or
$im->setUsernames("username_first, username_second, username_third");

$result = $im->create();

if (!$result) {

	// Log the error
	$error = $im->getError();
}
```

### OPEN DIRECT MESSAGE

```php
$im = new \ATDev\RocketChat\Ims\Im("[DIRECT MESSAGE ID]");

$result = $im->open();

if (!$result) {

	// Log the error
	$error = $im->getError();
}
```

### CLOSE DIRECT MESSAGE

```php
$im = new \ATDev\RocketChat\Ims\Im("[DIRECT MESSAGE ID]");

$result = $im->close();

if (!$result) {

	// Log the error
	$error = $im->getError();
}
```

### GET COUNTERS OF DIRECT MESSAGES

```php
$im = new \ATDev\RocketChat\Ims\Im("[DIRECT MESSAGE ID]");
$im->setUsername("[USERNAME]");

$result = $im->counters();

if (!$result) {

	// Log the error
	$error = $im->getError();
}
```

### GET MESSAGES FROM A DIRECT MESSAGE

```php
$im = new \ATDev\RocketChat\Ims\Im("[DIRECT MESSAGE ID]");

$result = $im->history([
    "latest" => "2016-09-30T13:42:25.304Z",
    "oldest" => "2016-05-30T13:42:25.304Z",
    "inclusive" => true,
    "unreads" => true
]);

if (!$result) {

	// Log the error
	$error = $im->getError();
}
```

### LISTS ALL THE DIRECT MESSAGES ON THE SERVER

```php
$im = \ATDev\RocketChat\Ims\Im::listEveryone();

if (!$result) {

	// Log the error
	$error = $im->getError();
}
```

### LISTS THE USERS OF PARTICIPANTS OF A DIRECT MESSAGE

```php
$im = new \ATDev\RocketChat\Ims\Im();

$im->setDirectMessageId("[DIRECT MESSAGE ID]");
// or
$im->setUsername("[USERNAME]");

$result = $im->members();

if (!$result) {

	// Log the error
	$error = $im->getError();
}
```

### RETRIEVES THE MESSAGES FROM ANY DIRECT MESSAGE IN THE SERVER

```php
$im = new \ATDev\RocketChat\Ims\Im("[DIRECT MESSAGE ID]");

$result = $im->messagesOthers();

if (!$result) {

	// Log the error
	$error = $im->getError();
}
```

### LISTS ALL THE SPECIFIC DIRECT MESSAGE ON THE SERVER

```php
$im = new \ATDev\RocketChat\Ims\Im();

$im->setDirectMessageId("[DIRECT MESSAGE ID]");
// or
$im->setUsername("[USERNAME]");

$result = $im->messages();

if (!$result) {

	// Log the error
	$error = $im->getError();
}
```

### SETS THE TOPIC FOR THE DIRECT MESSAGE

```php
$im = new \ATDev\RocketChat\Ims\Im("[DIRECT MESSAGE ID]");

$result = $im->setTopic("[MESSAGE]");

if (!$result) {

	// Log the error
	$error = $im->getError();
}
```