<?php
    session_start();
    include 'products.php';
    error_reporting(E_STRICT);
    $productCount = count($products);

    if (empty($_SESSION["quant"])) {
        foreach ($products as $key => $val) {
            $_SESSION["quant"][$key] = 0;
        }
    }

    if (!empty($_GET["action"])) {
        switch($_GET["action"]) {
            case "add":
                $productID = $_GET["id"];
                $productName = $products[$productID]["name"];
                $productPrice = $products[$productID]["price"];

                $cartItem = [
                    $productID => [ 
                        "id" => $productID, 
                        "name" => $productName, 
                        "price" => $productPrice
                    ],
                ];

                if (empty($_SESSION["cart"][$productID])) {
                    $_SESSION["cart"][$productID] = $cartItem[$productID];
                }

                $_SESSION["quant"][$productID] += 1;

                header("Location: index.php");
            break;
            case "remove":
                if (!empty($_SESSION["cart"])) {
                    foreach ($_SESSION["cart"] as $key => $val) {
                        if ($_GET["id"] == $key) {
                            unset($_SESSION["cart"][$key]);
                            unset($_SESSION["quant"][$key]);
                        }
                    }
                }

                header("Location: index.php");
            break;
            case "empty":
                unset($_SESSION["cart"]);
                unset($_SESSION["quant"]);
                header("Location: index.php");
            break;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Tool Shop</title>
</head>
<body>
    <h1>Tool Shop</h1>
    <a href="index.php?action=empty">Empty Cart</a>
    <div id="shopping-cart">
        <h2>Shopping Cart</h2>
        <?php
            if (empty($_SESSION["cart"])) {
                echo "Your shopping cart is empty.";
            } else {
        ?>

        <table class="tableClass">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Remove From Cart</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $cartPrice = 0;
                    $cartTotal = 0;

                    foreach ($_SESSION["cart"] as $key => $val) {
                        $prodPrice = number_format(
                            $_SESSION["cart"][$key]["price"],
                            2, ".", ","
                        );
                        $prodQuant = $_SESSION["quant"][$key];
                        $prodTot = number_format(
                            $prodPrice * $prodQuant,
                            2, ".", ","
                        );

                        $cartPrice += $_SESSION["cart"][$key]["price"];
                        $cartTot += $prodTot;
                        
                        echo "
                        <tr>
                            <td>".$_SESSION["cart"][$key]["name"]."</td>
                            <td>\$".$prodPrice."</td>
                            <td>".$prodQuant."</td>
                            <td>\$".$prodTot."</td>
                            <td>
                                <a href=\"index.php?action=remove&id=".$key."\">Remove From Cart</a>
                            </td>
                        </tr>
                        ";
                    }
                ?>
                <tr>
                    <td style="text-align: center;"><strong>Grand Total</strong></td>
                    <td>n/a</td>
                    <td>n/a</td>
                    <td><?php echo "\$".number_format(
                            $cartTot,
                            2, ".", ","
                        ); ?>
                    </td>
                    <td>n/a</td>
                </tr>
            </tbody>
        </table>

        <?php
            }
        ?>
    </div>
    <div id="products-table">
        <h2>Product Table</h2>
        <table class="tableClass">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Add to Cart</th>
                </tr>
            </thead>
            <tbody>

        <?php
            $totalPrice = 0;

            for ($x = 0; $x < $productCount; $x++) {
                $currentPrice = number_format($products[$x]["price"], 2, ".", ",");
                $totalPrice += $currentPrice;

                echo "
                    <tr>
                        <td>{$products[$x]["name"]}</td>
                        <td>\$".$currentPrice."</td>
                        <td>
                            <a href=\"index.php?action=add&id=".$x."\">Add to Cart</a>
                        </td>
                    </tr>
                ";
            }
        ?>
        
                <tr>
                    <td style="text-align: center;"><strong>Total</strong></td>
                    <td><?php echo "\${$totalPrice}" ?></td>
                    <td>n/a</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>