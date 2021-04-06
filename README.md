# kw_clipr

## CLI Processor 

Basic framework for running your scripts from CLI in a bit prettier package. It calls task
from predefined sources and allows them to run. Based on django cli and private CliC. You
can use it as base for running your own scripts like regular checks or menial tasks. All
that with simplified write to CLI output. All that with coloring and runable from web
interface, *nix or Windows CLI. As extra you will get a table engine which creates output
in markdown syntax.

Command line query is simple: ```clipr task --rest-of-params -t here```.
It uses kw_input for determination which params came.


## PHP Installation

### Direct usage

Install and set PHP on target machine. Then download this project and by following steps fill it by tasks suited for
your needs.

1.) Download clipr somewhere / install via Composer

2a.) For *nix check if your base script with bootstrap can be executed

2b.) For Windows check if you have PHP installed and in %PATH%

3.) Run Clipr from /bin without parameters to test if it works; You must be inside the project dir.

4a.) Here you probably copy clipr initial file to somewhere for better access for users.

4b.) Then it's necessary to include your own autoloader in that file. Preset one probably will not work.

4c.) And you need to set correct paths to basic clipr tasks, mainly due different Composer paths.

5.) Call your clipr initial file and check if it works again. Try Listing for check tasks.

6.) Make another directory with your tasks and fill them with classes based on your use-case.

6.) Call them and check if everything runs


### Composer

```
{
    "require": {
        "alex-kalanis/kw_clipr": ">=1.0"
    }
}
```

(Refer to [Composer Documentation](https://github.com/composer/composer/blob/master/doc/00-intro.md#introduction) if you are not
familiar with composer)


## PHP Usage

Each task is stored in preset directories in which it's possible to find them the fast way.
The paths are set in initial file.
And each task is subclass of kw_clipr\Tasks\ATask, which allows to write outputs and call params.

For running task simply call ```your/path/to/clipr task/name --task-params```

## Caveats

In default setup it contains subparts of kw_autoload and kw_inputs projects - both
are necessary to run without other dependencies. If you install this via Composer you'll see
kw_input twice and kw_autoload as extra weight. But that's okay. kw_autoload doesn't see
composer files if they aren't in predefined paths where kw_autoload can look for them.
For default run it isn't necessary to use the whole machine of Composer and it has been
developed without it.

Another thing is necessity to set basic path for getting files - then every file is relative
to that path. But then it's possible to "translate" that paths into files and work with them
as normal files in kw_* system. This means processing them as if they came from _FILES.

And at last - there is NO dependency injection support by default. Because that usually
means just Composer and that's the thing I want to avoid. Usual DI libraries are very
dependent on Composer. And the whole PSR has been made with Composer in mind. Also original
project CliC had no DI support.
