<?php
class ProductData {
    public function get($name) {}
    public function set($name, $value) {}
    
}


class ProductProcessor {
    public function save(ProductData $data) {}
    public function update(ProductData $data) {}
    public function delete($productId) {}

}

class ProductOutput{

    public function show() {}
    public function print() {}

}
