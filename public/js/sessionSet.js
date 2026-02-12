function sessionSet(name, object)
{
    // Object.entries(object).forEach(([key, value]) => 
    // {
    //     sessionStorage.setItem(key, value)
    // });
    sessionStorage.setItem(name, JSON.stringify(object));
    console.log("bagh");
}