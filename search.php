<?php
require_once "./template/header.php";
require_once "./functions/database_functions.php";

// Connect to the database
$conn = db_connect();

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

    // public function search($title) {
    //     return $this->searchNode($this->root, $title);
    // }

    // private function searchNode($node, $title) {
    //     if ($node === null) {
    //         return null;
    //     }
    //     if ($title === $node->title) {
    //         return $node;
    //     }
    //     if (strcmp($title, $node->title) < 0) {
    //         return $this->searchNode($node->left, $title);
    //     } else {
    //         return $this->searchNode($node->right, $title);
    //     }
    // }

    public function inOrderTraversal($node, &$result) {
        if ($node !== null) {
            $this->inOrderTraversal($node->left, $result);
            $result[] = $node;
            $this->inOrderTraversal($node->right, $result);
        }
    }
}

// Get the search term from the user
$searchTerm = $_GET['title'] ?? '';  // Assuming the search term is passed as a query parameter

// Fetch all book data in ascending order
$sql = "SELECT * FROM books ORDER BY book_title ASC";
$result = $conn->query($sql);

// Build the BST from the fetched data
$bst = new BinarySearchTree();
if ($result->num_rows > 0) {
    while ($book = $result->fetch_assoc()) {
        $bst->insert($book['book_title'], $book['book_author'], $book['book_isbn'], $book['book_image']);
    }
}

// Pagination logic
$limit = 8; // Number of results per page
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $limit;

// Get all nodes in order
$allBooks = [];
$bst->inOrderTraversal($bst->root, $allBooks);

// Filter books based on the search term
$filteredBooks = array_filter($allBooks, function($book) use ($searchTerm) {
    if (preg_match('/^[a-zA-Z]$/', $searchTerm)) {
        // Check if the title starts with the search term (case-insensitive)
        return strcasecmp($book->title[0], $searchTerm) === 0;
    }
    return stripos($book->title, $searchTerm) !== false || stripos($book->author, $searchTerm) !== false;
});

// Get the total number of results
$totalResults = count($filteredBooks);
$totalPages = ceil($totalResults / $limit);

// Get the results for the current page
$currentBooks = array_slice($filteredBooks, $offset, $limit);

// Display the results
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a, .pagination span {
            margin: 0 5px;
            padding: 8px 16px;
            text-decoration: none;
            color: #007bff;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .pagination a:hover {
            background-color: #ddd;
        }

        .pagination .current-page {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
        }
    </style>
</head>
<body>
    <div class="container">

    <div class="search-container text-center my-4">
        <form action="search.php" method="get">
            <input type="text" name="title" placeholder="Search for a book" required value="<?php echo $searchTerm;?>">
            <button type="submit">Search</button>
        </form>
    </div>

        <h1>Search Results for: "<?php echo htmlspecialchars($searchTerm); ?>"</h1>
        <div class="row">
            <?php foreach ($currentBooks as $book): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 py-2 mb-2">
                    <a href="book.php?bookisbn=<?php echo $book->isbn; ?>" class="card rounded-0 shadow book-item text-reset text-decoration-none">
                        <div class="img-holder overflow-hidden">
                            <img class='img-top' src='./bootstrap/img/<?php echo $book->image; ?>'>
                        </div>
                        <div class="card-body">
                            <div class="card-title fw-bolder h5 text-center"><?= htmlspecialchars($book->title) ?></div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Display pagination controls -->
        <div class='pagination'>
            <?php if ($page > 1): ?>
                <a href='search.php?title=<?php echo urlencode($searchTerm); ?>&page=<?php echo $page - 1; ?>'>&laquo; Previous</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $page): ?>
                    <span class='current-page'><?php echo $i; ?></span>
                <?php else: ?>
                    <a href='search.php?title=<?php echo urlencode($searchTerm); ?>&page=<?php echo $i; ?>'><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <a href='search.php?title=<?php echo urlencode($searchTerm); ?>&page=<?php echo $page + 1; ?>'>Next &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>