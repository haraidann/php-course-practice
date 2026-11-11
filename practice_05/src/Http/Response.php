<?php
declare(strict_types=1);
namespace Course\Http;

final readonly class Response
{
    public function __construct(public int $status, public string $body, public array $headers = []) {}
    public static function html(string $body, int $status = 200): self
    {
        return new self($status, $body, ['Content-Type' => 'text/html; charset=UTF-8']);
    }
    public static function redirect(string $path): self { return new self(303, '', ['Location' => $path]); }
    public function withHeader(string $name, string $value): self { return new self($this->status, $this->body, [$name => $value] + $this->headers); }
    public function send(string $method): void
    {
        // TODO P04-RESPONSE: Добавить X-Request-Id и корректно отправлять ответы, включая HEAD.
        //throw new \RuntimeException('TODO P04-RESPONSE');
        http_response_code($this->status);

        foreach($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        if ($method !== 'HEAD' && $this->status !== 204) {
            echo $this->body;
        }
    }
}
