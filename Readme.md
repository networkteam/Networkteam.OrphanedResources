# Flow Package Networkteam.OrphanedResources

Provides a command that removes orphaned files in `Data/Persistent/Resources` which have no resource entity in db.
They are safe to delete.

This is the counterpart to the Flow command `resource:clean`.

## Usage

```shell
./flow orphanedresource:remove --execute --minimum-age=3600
```

## Why is it necessary?

In a perfect world it's not necessary, but when a resource is created in filesystem and an exception or service outage
occurs before it's persisted in database then you have such orphaned resource files.