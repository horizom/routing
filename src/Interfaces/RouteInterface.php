<?php

namespace Horizom\Routing\Interfaces;

use Closure;
use Horizom\Dispatcher\MiddlewarePipe;
use Psr\Http\Server\MiddlewareInterface;

interface RouteInterface
{
    public const METHOD_GET = 'GET';
    public const METHOD_HEAD = 'HEAD';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_OPTIONS = 'OPTIONS';
    public const METHOD_PATCH = 'PATCH';

    public function getName(): string;

    public function getPath(): string;

    /**
     * @return string[]
     */
    public function getMethods(): array;

    /**
     * @return MiddlewareInterface[]|string[]
     */
    public function getMiddlewares(): array;

    public function getHandler(): Closure;

    public function getPipe(): MiddlewarePipe;

    public function setName(string $name): self;

    /**
     * Add middleware to route
     *
     * @param MiddlewareInterface|string $middleware
     * @return self
     */
    public function withMiddleware($middleware): self;

    /**
     * Add attribute to route
     *
     * @param string $key
     * @param mixed $value
     *
     * @return self
     */
    public function withAttribute(string $key, $value): self;

    /**
     * Get route attribute
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function getAttribute(string $key, $default = null);

    /**
     * Get route attributes
     *
     * @return array<string, mixed>
     */
    public function getAttributes(): array;

    /**
     * Compile route
     *
     * @param mixed[] $args
     */
    public function compile(array $args): void;

    /**
     * Is route compiled?
     *
     * @return bool
     */
    public function isCompiled(): bool;
}
