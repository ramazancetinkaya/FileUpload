# FileUpload
File upload class 

## Here's an example of how you can use it in a form:
```php
  try {
    $uploader = new FileUpload('uploads');
    $fileName = $uploader->upload($_FILES['file']);
    echo "File uploaded successfully as " . $fileName;
  } catch (Exception $e) {
    echo "Error: " . $e->getMessage();
  }
```

### Author

**Ramazan Çetinkaya**

* [github/ramazancetinkaya](https://github.com/ramazancetinkaya)

### License

Copyright © 2023, [Ramazan Çetinkaya](https://github.com/ramazancetinkaya).
