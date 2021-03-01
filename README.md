# kw_clipr

## CLI Processor 

Basic framework for running your scripts from CLI in a bit prettier package. Based on django cli
and private CliC. You can use it as base for running your own scripts like regular checks or
menial tasks. All that with simplified write to CLI output. All that with coloring and runable
from web interface, *nix or Windows CLI. As extra you will have a table engine which creates
output in markdown syntax. 


## PHP Installation

### Direct usage

Install and set PHP on target machine. Then download this project and fill it by tasks suited for
your needs.

### Composer

```
{
    "require": {
        "alex-kalanis/kw_clipr": "*"
    }
}
```

(Refer to [Composer Documentation](https://github.com/composer/composer/blob/master/doc/00-intro.md#introduction) if you are not
familiar with composer)


## PHP Usage

1.) Download somewhere

2a.) For *nix check if your base script with bootstrap can be executed

2b.) For Windows check if you have PHP installed and in %PATH%

3.) Run Clipr from /bin without parameters

4.) Make another directory with your tasks and fill them with classes based on your use-case 

5.) Call them and check they runs

## Caveats

In default setup it contains subparts of kw_autoload and kw_inputs projects - both
are necessary to run without other dependencies. If you install this via Composer you'll see
kw_input twice and kw_autoload as extra weight. But that's okay. kw_autoload doesn't see
composer files if they aren't in predefined paths where kw_autoload can look for them.
For default run it isn't necessary to use the whole machine of Composer and it has been
developed without it.

Another thing is necessity to set basic path for getting files - then every file is relative
to that path. But then it's possible to "translate" that paths into files and work with them
as normal files in kw_* system.
