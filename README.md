FernandoSpritesBundle
=====================

Installation
------------

Add to composer.json:

    {
        "require": {
            "fernando/sprites-bundle": "master"
        },
        "repositories": [
            {
                "type": "package",
                "package": {
                    "version": "master",
                    "name": "fernando/sprites-bundle",
                    "source": {
                        "url": "https://github.com/kirill-zabarniuk/sprites-bundle.git",
                        "type": "git",
                        "reference": "master"
                    },
                    "autoload": {
                        "psr-0": { "Fernando\\Bundle\\SpritesBundle": "" }
                    },
                    "target-dir": "Fernando/Bundle/SpritesBundle"
                }
            }
        ]
    }

and update vendors:

    php composer.phar update
