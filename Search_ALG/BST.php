<?php
class TreeNode {
    public $data;
    public $left;
    public $right;

    public function __construct($data) {
        $this->data = $data;
        $this->left = null;
        $this->right = null;
    }
}

class BinarySearchTree {
    public $root;

    public function __construct() {
        $this->root = null;
    }

    public function insert($data) {
        $newNode = new TreeNode($data);
        if ($this->root === null) {
            $this->root = $newNode;
        } else {
            $this->insertNode($this->root, $newNode);
        }
    }

    private function insertNode($node, $newNode) {
        if (strcasecmp($newNode->data['book_title'], $node->data['book_title']) < 0) {
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

    public function search($node, $query) {
        $results = [];
        $this->searchNode($node, $query, $results);
        return $results;
    }

    private function searchNode($node, $query, &$results) {
        if ($node !== null) {
            if (stripos($node->data['book_title'], $query) !== false || stripos($node->data['book_author'], $query) !== false) {
                $results[] = $node->data;
            }
            $this->searchNode($node->left, $query, $results);
            $this->searchNode($node->right, $query, $results);
        }
    }
}