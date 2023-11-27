<?php
interface Eater
{
    public function eat();
}
interface Flyer
{
    public function fly();
}
class Swallow implements Eater, Flyer
{
    public function eat() { /* implementation */ }
    public function fly() { /* implementation */ }
}
class Ostrich implements Eater
{
    public function eat() { /* implementation */ }
}
