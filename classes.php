<?php

class Admin
{

    public function __construct()
    {
        include 'dbconfig.php';
        $this->db = $conn;
    }

    public function __destruct()
    {
        $this->db->close();
    }

    function Store()
    {
        if (isset($_POST)) {
            $unique_id = uniqid(15);
            $user = 'Automatic';
            $product = $_POST['product_name'];
            $qty = $_POST['product_qty'];
            $sql = $this->db->prepare("INSERT INTO products (unique_id, name, quantity, added_by) VALUES (?, ?, ?, ?)");
            $sql->execute(array($unique_id, $product, $qty, $user,));
            if ($sql) {
                return "<script>
                            location.href = 'index.php?status=success&msg=".base64_encode(urlencode('Product Added Succesful!'))."';
                        </script>";
            }
        }
    }

    function Update()
    {
        if (isset($_POST)) {
            $unique_id = $_GET['pid'];
            $query = $this->db->prepare("SELECT * FROM products WHERE unique_id = ?");
            $query->bind_param('s', $unique_id);
            $query->execute();
            $row = $query->get_result();
            
            if ($row->num_rows == 1) {
                $product = $_POST['product_name'];
                $qty = $_POST['product_qty'];
                $sql = $this->db->prepare("UPDATE products SET name = ?, quantity = ? WHERE unique_id = ?");
                $sql->bind_param('sss', $product, $qty, $unique_id);
                if ($sql->execute()) {
                    return "<script>
                                location.href = 'index.php?status=success&msg=".base64_encode(urlencode('Product Updated Succesful!'))."';
                            </script>";
                } else {
                    return "<script>
                                location.href = 'index.php?status=failed&msg=".base64_encode(urlencode('Failed to Update Product!'))."';
                            </script>";
                }
            } else {
                return "<script>
                            location.href = 'index.php?status=invalid&msg=".base64_encode(urlencode('Invalid Product'))."';
                        </script>";
            }
        }
    }

    function Delete()
    {
        if (isset($_POST)) {
            $unique_id = $_GET['pid'];
            $query = $this->db->prepare("SELECT * FROM products WHERE unique_id = ?");
            $query->bind_param('s', $unique_id);
            $query->execute();
            $row = $query->get_result();
            
            if ($row->num_rows == 1) {
                $sql = $this->db->prepare("DELETE FROM products WHERE unique_id = ?");
                $sql->bind_param('s', $unique_id);
                if ($sql->execute()) {
                    return "<script>
                                location.href = 'index.php?status=success&msg=".base64_encode(urlencode('Product Deleted Succesful!'))."';
                            </script>";
                } else {
                    return "<script>
                                location.href = 'index.php?status=failed&msg=".base64_encode(urlencode('Failed to Delete Product!'))."';
                            </script>";
                }
            } else {
                return "<script>
                            location.href = 'index.php?status=invalid&msg=".base64_encode(urlencode('Invalid Product'))."';
                        </script>";
            }
        }
    }
}
