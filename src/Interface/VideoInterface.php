<?php

namespace App\Interface;

interface VideoInterface
{

    public function getPath(): ?string;

    public function setPath(string $path): self;

    public function getThumbnail(): ?string;

    public function setThumbnail(string $thumbnail): self;
}
