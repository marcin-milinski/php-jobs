<?php

namespace MatrixIsland;

ini_set('display_errors', 'on');
error_reporting(E_ALL & ~E_DEPRECATED);

/**
 * MatrixIsland
 */
class MatrixIsland
{
    public static $islandsCount = 0;
    public static $matrix = [
                                [1, 0, 0, 0, 1],
                                [1, 0, 0, 1, 1],
                                [1, 0, 1, 0, 0],
                                [0, 0, 1, 0, 1],
                                [1, 0, 0, 0, 1]
                            ];
    // stores matrix elements that have been already checked/tested by the algorithm
    private static $island_elements = [];

    /**
     * Returns a number of islands, which are basically cells filled with 1 that are adjecent to each other
     * @param array $matrix
     */
    public static function numberOfIslands()
    {        
        for ($i = 0; $i < count(static::$matrix); $i++) {
            for ($k = 0; $k < count(static::$matrix[$i]); $k++) {
                if (static::$matrix[$i][$k] === 1 && !isset(static::$island_elements[$i][$k])) {
                    // examine adjecent/surrounding cells in order to exclude them from being able to create a separate island
                    // if they are connected to the current cell
                    static::isAdjecentConnected($i, $k);
                    static::$islandsCount++;
                }
            }
        }
    }
    
    /**
     * A helper method to examine adjecent (surrounding) cells to find out if they belong to the same island (group of 1)
     * @param int $x
     * @param int $y
     */
    public static function isAdjecentConnected(int $x, int $y)
    {
        // aux arrays to make checking adjecent positions (top, right, bottom and left) easier
        // first row (above current row pos): -1, second row left & right: 0 & 0 (middle is current pos), third row (right under current pos): 1
        $x_positions = [-1, 0, 0, 1];
        // first column on the left of current col: 0, second column, above & below (middle is current pos): -1 & 1, third column on the right hand side of current pos: 0
        $y_positions = [0, -1, 1, 0];
        
        // mark the position as already checked
        static::$island_elements[$x][$y] = true;
        // check positions around current cell to discover more cells with number 1
        for ($i = 0; $i < count($x_positions); $i++) {
            $adjecent_x = $x_positions[$i] + $x;
            $adjecent_y = $y_positions[$i] + $y;
            // check first if [x,y] position exists within a matrix and has not been visited yet
            // eg. when examining first row and/or column, it's obvious that there's nothing on the right hand side or above
            if (isset(static::$matrix[$adjecent_x][$adjecent_y]) && static::$matrix[$adjecent_x][$adjecent_y] === 1 && !isset(static::$island_elements[$adjecent_x][$adjecent_y])) {
                static::isAdjecentConnected($adjecent_x, $adjecent_y);
            }
        }
        
    }

}

MatrixIsland::numberOfIslands();
echo MatrixIsland::$islandsCount;