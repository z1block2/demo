<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="table" width="100%">
                        <tr>
                            <th style="width: 30%">token/币别</th>
                            <th>balance/余额</th>
                            <th>action/操作</th>
                        </tr>
                        @foreach($tokens as $tokenName)
                            <tr>
                                <td>{{$tokenName}}</td>
                                <td>{{$balance[$tokenName] ?? '0'}}</td>
                                <td style="text-align:right;">
                                    <a href="/payment/deposit" type="button" class="inline-block px-6 py-2.5 bg-black rounded shadow-md ">存币</a>
                                    <a href="/payment/withdraw" type="button" class="inline-block px-6 py-2.5 bg-black rounded shadow-md ">提币</a>

                                </td>
                            </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
