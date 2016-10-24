<?php namespace App\Repositories\Elastic;

class AdviceRepository
{
	protected $eloquent;

	protected $elastic;

	public function __construct( $elastic, $eloquent)
	{
		$this->elastic = $elastic;
		$this->eloquent = $eloquent;
	}

	protected $priority = [
        1 => '40',
        2 => '30',
        3 => '20',
        4 => '10'
    ];

	public function setEloquent($eloquent)
	{
		$this->eloquent = $eloquent;
		return $this;
	}

	public function save(array $input, $advice = null)
	{
		$advice = is_null($advice) ? $this->eloquent : $advice;

		$advice->fill($input);
		
		$advice->save();

		$this->elastic->fill( $advice->toArray() );

		$this->elastic->save();

		return $advice;
	}

	public function delete($advice)
	{
		if( is_object( $advice ) )
		{
			$this->findElastic($advice->id)->delete();

			return $advice->delete();
		}
	}

	public function findElastic($id)
	{
		return $this->elastic->find($id);
	}

	public function paginate($userId, $total)
	{
		return $this->elastic->sort(['created_at' => 'desc'])->where('user_id',$userId)->paginate(10);
	}

	public function getGroupedByPriority( $userId )
	{
		$queries = [];

		foreach( $this->priority as $id => $total )
		{
			$queries[] = $this->elastic->where('priority',$id)->where('user_id', $userId)->sort(['created_at' => 'desc'])->limit($total)->build();
		}

		$results =  $this->elastic->union( $queries );

		$groupedAdvice = [];

		foreach($results as $result)
		{
			$groupedAdvice[ $result->priority ][] = ['content' => $result->content, 'id'=>$result->id, 'priority' => $result->priority ,'created_at' => $result->created_at];
		}

		return $groupedAdvice;
	}
}