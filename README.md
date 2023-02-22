# InternationalLotteries

#### Running application

* Clone or download repo https://github.com/Njuman/InternationalLotteries.git
* Open terminal and run `php cli.php` and follow the prompts

Threading isn't wired mainly I couldn't install a thread safe version of
PHP on M1 Mac Pro, hence couldn't test functionally.

Update `Runner.php` with the following code if you want to try out threading

```php
public function run(): void {
    foreach ($this->lotteries as $lotto) {
        $prefix = strtolower($lotto::PREFIX);
        $filesCollection = getFilesByDateRange('uploads', $prefix, $this->start, $this->end);

        foreach ($filesCollection as $drawId => $files) {
            $output = $this->save($prefix, $drawId);
            $entries = $files[1];
            $result = splFileObjectToArray($files[0])[1];
                
            $output->fputcsv(explode(';', $lotto::HEADER), ';');
                
            $callback = function(chuck) use ($result, $output) {
                $processor = new $lotto($chuck, $result, $output);
                $processor->call();
            }
                
            tm = new ThreadManager($entries, 4);
            tm.run();
        }
    }
}
```

#### Notes
I suspect I'm missing another level of abstraction to encapsulate file navigation
and objection traversal. Which instance of class will be injected into
process constructor then will have access to methods like `getResult` and also
`entries`



