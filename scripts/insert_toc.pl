#!/usr/bin/perl

$host = $ARGV[0];
$db = $ARGV[1];
$usr = $ARGV[2];
$pwd = $ARGV[3];

use DBI();

open(IN,"../xml/ssayyar.xml") or die "can't open ../xml/ssayyar.xml\n";

my $dbh=DBI->connect("DBI:mysql:database=$db;host=$host","$usr","$pwd");

$sth_enc=$dbh->prepare("set names utf8");
$sth_enc->execute();
$sth_enc->finish();

$sth1=$dbh->prepare("CREATE TABLE books_toc(
book_id varchar(4),
btitle varchar(2000),
level int(2),
title varchar(10000),
page varchar(20),
slno int(6) auto_increment, primary key(slno)) auto_increment=10001 ENGINE=MyISAM character set utf8 collate utf8_general_ci");

$sth1->execute();
$sth1->finish();

$line = <IN>;
$scount = 0;

while($line)
{
	chop($line);
	
	if($line =~ /<book bid="(.*)" btitle="(.*)">/)
	{
		$book_id = $1;
		$btitle = $2;
	}
	elsif($line =~ /<s([1-4]+)[\s]+page="(.*)"[\s]+title="(.*)">/)
	{
		$level = $1;
		$page = $2;
		$title = $3;

		insert_to_db($book_id,$btitle,$level,$title,$page);
		$title =  "";
		$level = "";
		$page = "";
	}
	elsif($line =~ /<\/s([1-4]+)>/)
	{
	}
	else
	{
		print $line . "\n";
	}

$line = <IN>;
}

close(IN);


sub insert_to_db()
{
	my($book_id,$btitle,$level,$title,$page) = @_;
	my($sth2);

	$btitle =~ s/'/\\'/g;
	$title =~ s/'/\\'/g;
  
    #~ print 'TOC->' . $book_id . "\n";
    
	$sth2=$dbh->prepare("insert into books_toc values('$book_id','$btitle','$level','$title','$page','')");
	$sth2->execute();
	$sth2->finish();
}

