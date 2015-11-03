## 0.8

Changes since 0.7

 - (Breaking) Normalize view events for included templates (folder/file.twig -> folder.file)
 - (initial) Twig 2.x support
 
## 0.7

Changes since 0.6

 - Support Laravel 5.x

## 0.6.0

Changes since 0.5.x

 - Support Laravel 4.2
 - Don't replace the ViewFactory to avoid package problems.
 - Improve the Twig syntax for calling aliases (#61)
 - Add Extensions for most used Laravel functions/filters (#99)
 - Improve template loading (#39, #60)
 - Compile twig files in `artisan optimize` command, extend CompilerEngine (#112)
 - Make better use of the IoC (#56, #119)
 - Improve Laravel composer / creator support (#59, #66)
 - Improve/simplify getAttributes() function
 - Render string templates (via ArrayLoader) (#94)

 - Improve error handling, use original source in Exceptions (#115)
