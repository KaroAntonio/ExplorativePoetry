<?php namespace App\Http\Controllers;

use App\Story;
use Request;
use DB;

class StoriesController extends Controller {
    
    private $story;
    
    public function __construct(Story $story) 
    {
        $this->story = $story;
    }
        
	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
        $stories = $this->story->get();
        
        //dd($stories);
        
		return view('index',compact('stories'));
	}
    
    public function begin()
	{
        //Find First Story
        $story = Story::find(1);
        
        //Set First Story (For Tweaking)
        $story->line = "Breathe";
        $story->save();
        
        $storyLine = [ $story ]; 
        $branches = [];
        $stories = [$storyLine, $branches];
        
        //dd($story);
        
        //return $this->show(1);
		return view('index',compact('stories'));
	}
    
    public function show($id)
    {
        //Find Current Story
        $story = Story::find($id);
        
        //Increment Story Visits
        $story->visits = $story->visits + 1;
        $story->save();
        
        //Find Branches
        $branches = Story::where('parentID', '=', $story->id)->get();
        
        //Find StoryLine
        $storyLine = [ $story ];
        
        //add all stories to storyline until beginning is found
        while ($story->id != 1) {
            $story = Story::find($story->parentID);
            array_push($storyLine, $story );
        }
        
        $stories = [$storyLine, $branches];
        //dd($stories);
        
        return view('index',compact('stories'));
    }
    
    public function store()
    {
        $input = Request::all();
        
        $line = $input['line'];
        //CHECK for empty lines
        if ($line == "") 
            return $this->show($input['parentID']);
        
        //CHECK for duplicate lines
        $line = trim ( strtolower ( $line ));
        $siblings = 
            Story::where('parentID', '=', $input['parentID'])->get();
        for ($x = 0; $x < sizeOf($siblings); $x++) {
            $siblingLine = trim ( strtolower ( $siblings[$x]->line ));
            if ($line == $siblingLine)
               return $this->show($siblings[$x]->id);
        }
        
        //STORE new story
        $story = new Story;
        $story-> line = $input['line'];
        $story-> parentID = $input['parentID'];
        $story-> visits = 0;
        $story-> save();
        
        return $this->show($story->id);
    }
    
}
