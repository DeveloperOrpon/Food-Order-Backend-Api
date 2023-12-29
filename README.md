
## Development & Testing

This package contains a Docker image for building a test suite and an analysis
container. You must have Docker installed on your system to run all tests using
the following command.

```bash
docker-compose run --rm --build tests
```

Run the static analyzer on the code base.

```bash
docker-compose run --rm --build analysis
```

## Security
