# Migration to Version 4.0

TODO

## Checklist

1. TODO

## New Features

- Every adapter which supports `metadata` now implements `MetadataCapableInterface` and provides a dedicated object containing all the metadata values it supports.

## Removed Classes and Traits

TODO

## Breaking Changes

- `AbstractAdapter` and `StorageInterface` are not aware of the methods `getMetadata` anymore. These were moved to the new `MetadataCapableInterface`
- `Capabilities` do not provide `supportedMetadata` anymore. The supported metadata is tied to the used storage adapter and thus, was already requiring projects to explicitly know the exact implementation of the cache backend in case of using these metadatas anyway
- `KeyListIterator` and the corresponding `IteratorInterface` does not provide the `mode` `CURRENT_AS_METADATA` anymore 
- `PluginOptions#getSerializer` does not create a serializer anymore if a `string` option was passed, instead, the `string` is returned
- Every method now has native return types
- Every property now has native types
- Every method argument now has native types
