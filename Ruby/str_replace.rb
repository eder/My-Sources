#Function similar to str_replace PHP
#Replace words in arrays
def str_replace (from, to, string)
  string = [from, to].transpose.reduce(string) do |buffer, (from, to)|
    buffer.gsub(from, to)
  end
end