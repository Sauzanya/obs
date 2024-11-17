<?php
class TreeNode {
    public $title;
    public $author;
    public $isbn;
    public $image;
    public $left;
    public $right;

    public function __construct($title, $author, $isbn, $image) {
        $this->title = $title;
        $this->author = $author;
        $this->isbn = $isbn;
        $this->image = $image;
        $this->left = null;
        $this->right = null;
    }
}

class BinarySearchTree {
    public $root;

    public function __construct() {
        $this->root = null;
    }

    public function insert($title, $author, $isbn, $image) {
        $newNode = new TreeNode($title, $author, $isbn, $image);
        if ($this->root === null) {
            $this->root = $newNode;
        } else {
            $this->insertNode($this->root, $newNode);
        }
    }

    private function insertNode($node, $newNode) {
        if (strcmp($newNode->title, $node->title) < 0) {
            if ($node->left === null) {
                $node->left = $newNode;
            } else {
                $this->insertNode($node->left, $newNode);
            }
        } else {
            if ($node->right === null) {
                $node->right = $newNode;
            } else {
                $this->insertNode($node->right, $newNode);
            }
        }
    }

    public function search($title) {
        return $this->searchNode($this->root, $title);
    }

    private function searchNode($node, $title) {
        if ($node === null) {
            return null;
        }
        if ($title === $node->title) {
            return $node;
        }
        if (strcmp($title, $node->title) < 0) {
            return $this->searchNode($node->left, $title);
        } else {
            return $this->searchNode($node->right, $title);
        }
    }

    public function inOrderTraversal($node, &$result) {
        if ($node !== null) {
            $this->inOrderTraversal($node->left, $result);
            $result[] = $node;
            $this->inOrderTraversal($node->right, $result);
        }
    }
}