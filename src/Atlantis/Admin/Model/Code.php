<?php

namespace Atlantis\Admin\Model;

use Baum\Node;


class Code extends Node {

    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'codes';
    protected $hidden = array('order_left', 'order_right', 'depth');

    // /**
    // * Column name which stores reference to parent's node.
    // *
    // * @var int
    // */
    protected $parentColumn = 'parent_id';

    // /**
    // * Column name for the left index.
    // *
    // * @var int
    // */
    protected $leftColumn = 'order_left';

    // /**
    // * Column name for the right index.
    // *
    // * @var int
    // */
    protected $rightColumn = 'order_right';

    // /**
    // * Column name for the depth field.
    // *
    // * @var int
    // */
    protected $depthColumn = 'depth';

    // /**
    // * With Baum, all NestedSet-related fields are guarded from mass-assignment
    // * by default.
    // *
    // * @var array
    // */
    protected $guarded = array('id', 'parent_id', 'order_left', 'order_right', 'depth');


    /**
     * Scope by Parent Name
     *
     * @param $query
     * @param $parent
     * @return mixed
     */
    public function scopeParentName($query,$parent){
        $code = $query->where('name','=',$parent)->first();

        if( $code )
            return $code->children()->get();
        else
            return $code;
    }


    /**
     * Scope Category
     *
     * @param $query
     * @param $category
     * @return mixed
     */
    public function scopeCategory($query,$category){
        return $query->where('category',$category);
    }


    /**
     * Scope Filtering
     *
     * @param $query
     * @param array $columns
     */
    public function scopeFiltering($query,$columns=array()){
        foreach($columns as $column => $value){
            $relations =  explode('.',$column);
            $field = array_pop($relations);

            $query->where($field,'LIKE',$value.'%');
        }
    }


    /**
     * Import - Import multi-level array into code
     *
     * @param $items
     * @param null $parent
     * @return null
     */
    public static function import($items,$parent=null){
        if( empty($items) ) return null;

        #i: Node attributes definition, other will treated as child
        $node_structure = array('category'=>array(),'name'=>array(),'value'=>array());

        #i: Iterate every item as node
        foreach($items as $node){
            $node_parent = null;

            #i: Get current valid node
            $node_current = array_intersect_key($node,$node_structure);

            #i: Check node valid to structure
            if( empty($node_current) ) continue;

            #i: Root or children node
            if( !isset($parent)  ) {
                #i: Create code node and becoming parent
                $node_parent = self::create($node_current);

            }else{
                #i: Children can be parent too
                $node_parent = $parent->children()->create($node_current);
            }

            #i: Another attribute as children node
            $node_childrens = array_diff_key($node,$node_structure);
            foreach($node_childrens as $node_children){
                if(!empty($node_children)) self::import($node_children,$node_parent);
            }
        }
    }

}
