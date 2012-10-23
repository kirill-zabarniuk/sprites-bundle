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
                        "url": "https://github.com/path-to/sprites-bundle.git",
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

Add to config.yml:

    fernando_sprites:
        java:       /usr/bin/java
        jar_packer: %kernel.root_dir%/Resources/java/spritetools-1.0.jar
        jar_yml:    %kernel.root_dir%/Resources/java/snakeyaml-1.9-SNAPSHOT.jar
        css:        ~

Update vendors:

    php composer.phar update

Defaul configuration
-------------

    fernando_sprites:
        java:       /usr/bin/java
        jar_packer: %kernel.root_dir%/Resources/java/spritetools-1.0.jar
        jar_yml:    %kernel.root_dir%/Resources/java/snakeyaml-1.9-SNAPSHOT.jar
        css:
            class: sprite
            filename: sprite.css
        assetic:
            enabled: false
            formula_filters: [sprite]
