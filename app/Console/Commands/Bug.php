<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Bug extends Command
{
    protected $signature = 'bug {count?} {--wrong} {--correct}';
    protected $description = 'Command description';

    public function handle()
    {
        $testCount = (int) (
            $this->argument("count") ?: $this->ask("How many examples do you want to show?", 10)   
        );

        $length = strlen($testCount);
        $format = "Array of %{$length}s + split of %{$length}s gives %{$length}s groups (%7.03f%%)";

        for ($i = 1; $i <= $testCount; $i++) {
            // Take an array of length $i
            $collection = collect(range(1, $i));

            // Split can only return at most one group per
            // element. So, if the split is greater than
            // the length of the array we can skip it
            for ($j = 1; $j <= $i; $j++) {
                $groups = $collection->split($j)->count();

                if ($this->option("wrong") && $groups === $j) {
                    continue;
                }
                
                if ($this->option("correct") && $groups !== $j) {
                    continue;
                }

                $this->output->writeln(vsprintf($format, [
                    $i,
                    $j,
                    $groups,
                    100 * $groups / $j,
                ]));
            }
        }
    }
}
