<?php

namespace Ecomm\Entity;

use DateTime;

class Post {

    private $id;
    private $title;
    private $content;
    private $publishedAt;
    private $author;

    public function __construct() {
        $this->publishedAt = new DateTime();
    }

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getPublishedAt() {
        return $this->publishedAt;
    }

    public function setPublishedAt(DateTime $publishedAt) {
        $this->publishedAt = $publishedAt;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor(User $author) {
        $this->author = $author;
    }

}
